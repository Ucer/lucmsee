<template>
</template>

<script>
    export default {
        props: {
            messageSession: false,
            messageType: '',
        },
        created: function () {
            if (this.messageSession) {
                this.$message({showClose: true, message: this.messageSession, type: this.messageType})
            }
        },
    }
</script>
