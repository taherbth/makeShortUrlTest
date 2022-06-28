# WAM
![ci/cd_badge](https://github.com/iquantilellc/WAM/actions/workflows/ci_cd.yml/badge.svg)
<img src="https://img.shields.io/badge/Last deployed at-Mon May 30 05:23:52 UTC 2022-blue.svg" alt="deploy_date_badge"/>
<img src="https://img.shields.io/badge/Latest-v1.0.59-purple.svg" alt="latest_version_badge"/>
&nbsp;

## Local setup
For frontend, 
- use node version 14
- use npm version 6

Try using [NVM](https://github.com/nvm-sh/nvm) for local
```shell
    nvm install 14
    nvm use 14
```
For backend, 
- use PHP version `7.4`. 
- create a database named `login`

Try using [phpenv](https://github.com/phpenv/phpenv) for local
```shell
phpenv local 7.4.0
```

run,
```sh
	composer install && npm install && cp .env-local .env && php artisan key:generate && composer require laravel/passport && php artisan migrate && php artisan passport:install && npm run dev
```

### Fresh migration
```sh
php artisan migrate:fresh && php artisan passport:install --force && php artisan serve
```

### Alternately - using docker
you can run this script which will host it inside multiple containers, available at [9080](http://localhost:9080/) 
```bash
bash ./local_setup.sh
```
#### Run migration and seed
Once your containers are running, run these commands.
```bash
docker exec -it php /usr/local/bin/php artisan migrate
docker exec -i mysql mysql -u newuser -ppasswor434 login < preq_data/wam_docker_faker.sql
```
These will migrate the database and populate with some required fake data.

#### Run queue worker
To send mail, set image, post large files we have used queue. You can also create queue inside container by following.
###### Set up and run
If you want to set up and run a queue worker in the container, run this command. 
```shell
docker exec -i -t php sh -c "exec >/dev/tty 2>/dev/tty </dev/tty && /usr/bin/screen -S queue php artisan queue:work"
```
Then press **Ctrl + A** and then **Ctrl + D** to detach from queue screen.

###### Check status
To see progress of current queue worker, run this command.
```shell
docker exec -i -t php screen -R queue
```
Again press **Ctrl + A** and then **Ctrl + D** to detach from queue screen.
###### Stop queue worker
To stop that queue worker, run this command.
```shell
docker exec -i -t php screen -XS queue quit
```
or, If you face any problem with the above command, run this one. It will distroy all screens.
```shell
docker exec -it php killall SCREEN
```

## Server deploy
WAM has a complete CI/CD pipeline built into it. To deploy your code what you need to do is to is listed below.

### Deployment guide 
- commit and push your code to your specific branch
```shell
git commit -m "your message" . && git push origin {{Your branch}}
```
- checkout to deployment branch and pull the latest changes
```shell
git checkout deployment . && git pull
```
- merge or pull from your branch into deployment
```shell
git pull origin {{Your branch}}
```
or 
```shell
git merge --no-ff origin {{Your branch}}
```
- After all, if you face any conflict, solve them and then push to deployment branch.
```shell
git push origin deployment
```

### Monitoring workflow

After pushing to deployment, necessary workflow should start running on GitHub which you can see in [here](https://github.com/iquantilellc/WAM/actions).

### Underlying architecture

GitHub action is configured in such a way that, `on-push-branch-deployment` , A **continuous integration** job will make a production build for vue files with necessary packages. Then, commit plus push will be done by the GitHub action itself to the `deployment branch`. This will also tag necessary commits and generate new release. All these will occur in GitHub's own `ubuntu-latest` runner. <br> <br>
After that, a **continuous deployment** job would trigger the server to pull from GitHub, restart and rebase necessary configurations. This will all occur in our hosted runner. While the **continuous deployment** job is working, the application would put itself to maintenance mode for convenience. And also, an automated Slack notification will be sent to the **Wam development** channel after successful deployment via a custom Slack app. <br><br>
All deployment file configurations are listed [here](#configurations) 

<a name="access"></a>
### Accessing server
get the Privacy Enhanced Mail file (.pem) and current public IPv4 DNS to ssh into the server
```bash
ssh -i "{{ local .pem file location}}" {{user}}@{{Public IPv4 DNS}}
```
most likely, the user would be `ubuntu`

<a name="configurations"></a>
### Changing ci-cd configurations 

The **CI** runs on the GitHub workflow vps where the **CD** runs on our self-hosted runner. <br>
At the end of **CD** pipeline. You can configure it and other functionalities in `./server_deploy.sh`. <br>
Any part of **CI** can be configured in `./.github/workflows/ci_cd.yml`. <br>

### Issue regarding ci-cd
Go to GitHub [workflow](https://github.com/iquantilellc/WAM/actions) and find the failed job.

#### In case of `git pull` failed inside the serve due to authentication 
ssh into server and run 
```sh
gh auth status
```
If no user is logged in, login using 
```sh
gh auth login
```
>**NB**: If this become a frequent issue, you can try GitHub [deploy keys](https://docs.github.com/en/developers/overview/managing-deploy-keys#deploy-keys). It works same as SSH keys.

## Notable things

As one of the key feature of this software is related with video file. Some things you have to consider in terms of scaling. Most of the configurations work in a monolithic way. And due to the strict UX, media upload and transcoding is mostly done without event driven approach. Other notable things are...

### While uploading videos
Please ensure your os file path, this one's local codebase is for windows. Make necessary adjustments in all **upload_video and upload_video_info methods** .

### To upload large video file
Update post_max_size and upload_max_filesize in php.ini
>**Suggestion**: For future, I will suggest an event driven microservice implementation for media transcoding,which would largely help the app in terms of performance.
## Third party software

WAM leverages a lot of third party app and driver baked right into EC2 instance. The extra things we utilized, outside the **ubuntu**'s default packages are listed below.

#### [FFMPEG](https://www.gyan.dev/ffmpeg/builds/ffmpeg-release-full.7z) - Video conversion binary
download FFMPEG, add location to server environment variable and .env <br>
>**NB**: Image Rendering requires php [exif](https://www.php.net/manual/en/exif.installation.php) extension to be enabled

#### [Graphviz](https://graphviz.org/) - Generate ER diagram (For dev env only)
Setup Graphviz and [beyondcode package](https://github.com/beyondcode/laravel-er-diagram-generator), add to env variable and run to generate model based ER diagram
```sh
composer require beyondcode/laravel-er-diagram-generator --dev
php artisan generate:erd
```
I have already included a file in root directory of repo called [graph.png](https://github.com/iquantilellc/WAM/blob/deployment/graph.png) which represents the ER diagram of the project at the time of my writing.

#### [Beanstalk](https://beanstalkd.github.io/) - Queue driver
Simple, fast work queue. Almost as fast as redis but less memory hogging.

#### [Supervisor](http://supervisord.org/) - Process management
Automated Queue execution on multi-thread. Client/server system that allows its users to control a number of processes on Linux and UNIX-like operating systems. Used to run automated queues. <br>
You can change the config of supervisor from `/etc/supervisor/conf.d/laravel-worker.conf` <br>
In case of any error, [access server](#access) and see the log file at `/var/www/login/worker.log`

#### File - Cache driver
Not so fast but less resource usage.

## AWS services used
Some common AWS services are used for this software. Nothing too fancy right now. Go to `AWS Billing Dashboard` to see all active services. At the time of my writing active services are listed below. <br>

**EC2** - for hosting the main application <br>
**RDS** - for mysql database <br>
**S3** - for file storage, mostly course video files <br>
**Route53** - DNS management <br>
**Elastic load balancer** - Load balance in current VPC <br>
**CloudWatch** - Automated alarm if a service goes down and restart <br>
**SES** - for Email service

All these services are in free tier for now.<br>
If you want to configure these this app altogether. All configurations, passwords, key and important data for WAM can be found [here](https://drive.google.com/drive/folders/1M5XISvsEMnVOlOOMQnksKFPWHcsP8YlJ?usp=sharing). You can visit [official AWS documentation](https://docs.aws.amazon.com/) for more. <br>
>**Suggestion**: For future, I will suggest replacing `FFMPEG` with something like [Elastic Transcoder](https://aws.amazon.com/elastictranscoder/) or [Coconut](https://www.coconut.co/)

