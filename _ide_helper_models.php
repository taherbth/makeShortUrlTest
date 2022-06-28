<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Authority
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $profile_picture
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $stripe_product_id
 * @property string|null $stripe_price_id
 * @property string|null $stripe_tax_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|Authority newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Authority newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Authority query()
 * @method static \Illuminate\Database\Eloquent\Builder|Authority whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authority whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authority whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authority whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authority wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authority whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authority whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authority whereStripePriceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authority whereStripeProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authority whereStripeTaxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authority whereUpdatedAt($value)
 */
	class Authority extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BillingAddress
 *
 * @property int $id
 * @property int $country_id
 * @property int $zip_code
 * @property int $state_id
 * @property int $city_id
 * @property string $address_line_1
 * @property string|null $address_line_2
 * @property string $email
 * @property int $institution_id
 * @property int $is_default
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Institution|null $institution_ba
 * @method static \Illuminate\Database\Eloquent\Builder|BillingAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BillingAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BillingAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder|BillingAddress whereAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingAddress whereAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingAddress whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingAddress whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingAddress whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingAddress whereInstitutionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingAddress whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingAddress whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingAddress whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingAddress whereZipCode($value)
 */
	class BillingAddress extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Blog
 *
 * @property int $id
 * @property string $title
 * @property string $article
 * @property string $banner
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Blog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog query()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereArticle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereUpdatedAt($value)
 */
	class Blog extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\City
 *
 * @method static \Illuminate\Database\Eloquent\Builder|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City query()
 */
	class City extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Client
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string|null $secret
 * @property string|null $provider
 * @property string $redirect
 * @property bool $personal_access_client
 * @property bool $password_client
 * @property bool $revoked
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\AuthCode[] $authCodes
 * @property-read int|null $auth_codes_count
 * @property-read string|null $plain_secret
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\User|null $user
 * @method static \Laravel\Passport\Database\Factories\ClientFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client query()
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client wherePasswordClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client wherePersonalAccessClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereRedirect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereRevoked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereUserId($value)
 */
	class Client extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Contact
 *
 * @property int $id
 * @property string|null $attribute
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $name
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereValue($value)
 */
	class Contact extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Country
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\State[] $states
 * @property-read int|null $states_count
 * @method static \Illuminate\Database\Eloquent\Builder|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country query()
 */
	class Country extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Course
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $details
 * @property string $video_original_name
 * @property string|null $path
 * @property string|null $thumbnail
 * @property string|null $topic
 * @property int|null $chapter
 * @property int|null $episode
 * @property int|null $rating
 * @property int $is_public
 * @property int $is_draft
 * @property int $publishable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $durationInSec
 * @property int $upload_status
 * @property string $publishable_type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\courseQuestion[] $course_question_c
 * @property-read int|null $course_question_c_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\courseQuestionOption[] $course_question_option_c
 * @property-read int|null $course_question_option_c_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $publishable
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Student[] $student_c
 * @property-read int|null $student_c_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\studentResponses[] $student_response_c
 * @property-read int|null $student_response_c_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\watchHistory[] $watchHistory_c
 * @property-read int|null $watch_history_c_count
 * @method static \Illuminate\Database\Eloquent\Builder|Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course query()
 * @method static \Illuminate\Database\Eloquent\Builder|Course search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Course searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereChapter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereDurationInSec($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereEpisode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereIsDraft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course wherePublishableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course wherePublishableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereUploadStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereVideoOriginalName($value)
 */
	class Course extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Facilitator
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property int $institution_id
 * @property string|null $profile_picture
 * @property int $status
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $is_mailable
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Course[] $course_f
 * @property-read int|null $course_f_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\responseRating[] $response_rating_f
 * @property-read int|null $response_rating_f_count
 * @property-read \App\Models\Institution|null $school_f
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\FacilitatorFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Facilitator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Facilitator newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Facilitator query()
 * @method static \Illuminate\Database\Eloquent\Builder|Facilitator search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Facilitator searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Facilitator whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facilitator whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facilitator whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facilitator whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facilitator whereInstitutionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facilitator whereIsMailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facilitator whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facilitator wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facilitator whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facilitator whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facilitator whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facilitator whereUpdatedAt($value)
 */
	class Facilitator extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Faq
 *
 * @property int $id
 * @property string $question
 * @property string $answer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Faq newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Faq newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Faq query()
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereUpdatedAt($value)
 */
	class Faq extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Institution
 *
 * @property int $id
 * @property string $institution_name
 * @property string|null $institution_profile_picture
 * @property string|null $institution_address
 * @property string $institution_code
 * @property int $institution_primary_facilitator_quantity
 * @property int $institution_primary_student_quantity
 * @property string|null $admin_name
 * @property string $email
 * @property string|null $admin_profile_picture
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_approval
 * @property int $status
 * @property string|null $established_at
 * @property string|null $stripe_id
 * @property string $trial_ends_at
 * @property-read \App\Models\BillingAddress|null $billing_address_i
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Course[] $course_i
 * @property-read int|null $course_i_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Course[] $course_s
 * @property-read int|null $course_s_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Facilitator[] $facillitor_s
 * @property-read int|null $facillitor_s_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Invoice[] $invoice_s
 * @property-read int|null $invoice_s_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\responseRating[] $response_rating_i
 * @property-read int|null $response_rating_i_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Student[] $student_s
 * @property-read int|null $student_s_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Cashier\Subscription[] $subscriptions
 * @property-read int|null $subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\Transaction|null $transactions
 * @method static \Database\Factories\InstitutionFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Institution newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Institution query()
 * @method static \Illuminate\Database\Eloquent\Builder|Institution search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereAdminName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereAdminProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereEstablishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereInstitutionAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereInstitutionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereInstitutionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereInstitutionPrimaryFacilitatorQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereInstitutionPrimaryStudentQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereInstitutionProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereUserApproval($value)
 */
	class Institution extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Invoice
 *
 * @property int $id
 * @property int $base_price
 * @property int $quantity
 * @property int $tax
 * @property int $total_paid
 * @property string $status
 * @property string $cancel_at
 * @property string $subscription_id
 * @property int $institution_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $institution_name
 * @property string $admin_name
 * @property-read \App\Models\Institution|null $school_inv
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereAdminName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereBasePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereCancelAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereInstitutionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereInstitutionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereTotalPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereUpdatedAt($value)
 */
	class Invoice extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Member
 *
 * @property int $id
 * @property string $name
 * @property string $role
 * @property string|null $image
 * @property string|null $facebook
 * @property string|null $linkedin
 * @property string|null $instagram
 * @property int $is_admin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $admin_message
 * @method static \Illuminate\Database\Eloquent\Builder|Member newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member query()
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereAdminMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereInstagram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereLinkedin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereUpdatedAt($value)
 */
	class Member extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Service
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $banner
 * @property string $button_name
 * @property string $button_address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereButtonAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereButtonName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereUpdatedAt($value)
 */
	class Service extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\State
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $cities
 * @property-read int|null $cities_count
 * @method static \Illuminate\Database\Eloquent\Builder|State newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State query()
 */
	class State extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Student
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $profile_picture
 * @property int $institution_id
 * @property int $status
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $is_mailable
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Course[] $course_c
 * @property-read int|null $course_c_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Institution|null $school_st
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\studentResponses[] $studentResponses_st
 * @property-read int|null $student_responses_st_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\watchHistory[] $watchHistory_st
 * @property-read int|null $watch_history_st_count
 * @method static \Database\Factories\StudentFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Student newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Student newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Student query()
 * @method static \Illuminate\Database\Eloquent\Builder|Student search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Student searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereInstitutionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereIsMailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereUpdatedAt($value)
 */
	class Student extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Transaction
 *
 * @property int $id
 * @property int $institution_id
 * @property string $transaction_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereInstitutionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 */
	class Transaction extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\courseQuestion
 *
 * @property int $id
 * @property string $question
 * @property string $question_type
 * @property string|null $answer_placeholder
 * @property string|null $answer_length
 * @property string|null $answer_min_length
 * @property string|null $answer_max_length
 * @property int $course_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Course|null $course_cq
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\courseQuestionOption[] $course_question_option_cq
 * @property-read int|null $course_question_option_cq_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\studentResponses[] $student_response_cq
 * @property-read int|null $student_response_cq_count
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestion whereAnswerLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestion whereAnswerMaxLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestion whereAnswerMinLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestion whereAnswerPlaceholder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestion whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestion whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestion whereQuestionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestion whereUpdatedAt($value)
 */
	class courseQuestion extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\courseQuestionOption
 *
 * @property int $id
 * @property int $course_question_id
 * @property string $courseQuestion_option
 * @property int|null $is_answer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\courseQuestion|null $course_question_cqo
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\courseQuestionOptionAnswer[] $course_question_option_answer_cqo
 * @property-read int|null $course_question_option_answer_cqo_count
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestionOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestionOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestionOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestionOption whereCourseQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestionOption whereCourseQuestionOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestionOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestionOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestionOption whereIsAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestionOption whereUpdatedAt($value)
 */
	class courseQuestionOption extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\courseQuestionOptionAnswer
 *
 * @property int $id
 * @property int $course_question_option_id
 * @property string $courseQuestionOption_answer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\courseQuestionOption|null $course_question_option_cqoa
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestionOptionAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestionOptionAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestionOptionAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestionOptionAnswer whereCourseQuestionOptionAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestionOptionAnswer whereCourseQuestionOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestionOptionAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestionOptionAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|courseQuestionOptionAnswer whereUpdatedAt($value)
 */
	class courseQuestionOptionAnswer extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\responseRating
 *
 * @property int $id
 * @property int $student_responses_id
 * @property int $rateable_id
 * @property int $response_rating
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $rateable_type
 * @property-read \App\Models\Facilitator|null $facillator_rr
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $rateable
 * @property-read \App\Models\studentResponses|null $student_response_rr
 * @method static \Illuminate\Database\Eloquent\Builder|responseRating newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|responseRating newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|responseRating query()
 * @method static \Illuminate\Database\Eloquent\Builder|responseRating whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|responseRating whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|responseRating whereRateableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|responseRating whereRateableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|responseRating whereResponseRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|responseRating whereStudentResponsesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|responseRating whereUpdatedAt($value)
 */
	class responseRating extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\studentResponses
 *
 * @property int $id
 * @property int $student_id
 * @property int $course_question_id
 * @property string $student_response
 * @property int $is_public
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\courseQuestion|null $course_question_sr
 * @property-read \App\Models\responseRating|null $response_rating_sr
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Student[] $student_sr
 * @property-read int|null $student_sr_count
 * @method static \Illuminate\Database\Eloquent\Builder|studentResponses newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|studentResponses newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|studentResponses query()
 * @method static \Illuminate\Database\Eloquent\Builder|studentResponses whereCourseQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|studentResponses whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|studentResponses whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|studentResponses whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|studentResponses whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|studentResponses whereStudentResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|studentResponses whereUpdatedAt($value)
 */
	class studentResponses extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\watchHistory
 *
 * @property int $id
 * @property int $course_id
 * @property int $student_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Student|null $course_wh
 * @property-read \App\Models\Student|null $student_wh
 * @method static \Illuminate\Database\Eloquent\Builder|watchHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|watchHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|watchHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|watchHistory whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|watchHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|watchHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|watchHistory whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|watchHistory whereUpdatedAt($value)
 */
	class watchHistory extends \Eloquent {}
}

