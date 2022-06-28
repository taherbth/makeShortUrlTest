<template>
    <div>
        <FacHeader v-if="User.role === 1" />
        <StdHeader v-else-if="User.role === 2" />
        <InsHeader v-else-if="User.role === 3" />
        <AutHeader v-else-if="User.role === 4" />
    </div>
</template>
<script>
    import StdHeader from './student/header'
    import FacHeader from './facilitator/header'
    import InsHeader from './institution/header'
    import AutHeader from './authority/header'

    export default {
        components: {
            StdHeader,
            FacHeader,
            InsHeader,
            AutHeader,
        }
    }
</script>
