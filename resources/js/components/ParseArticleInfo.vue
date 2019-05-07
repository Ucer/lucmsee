<template>
    <div v-if="!rawHtml" class="ui icon message info">
        <i class="notched circle loading icon"></i>
        <div class="content">
            <div class="header">稍候</div>
            <p>正在努力加载中。。。</p>
        </div>
    </div>
    <div v-else>
        <div class="ui readme markdown-body content-body" v-html="rawHtml">
        </div>
    </div>
</template>

<script>
    import marked from 'marked'

    export default {
        props: {
            content: {
                type: String,
                default() {
                    return null
                }
            }
        },
        data() {
            return {
                rawHtml: false,
            }
        },
        created() {
            this.rawHtml = marked(this.content)
            this.codeLineNumbers()
        },
        methods: {
            codeLineNumbers() {
                this.$nextTick(() => {
                    $('pre').addClass("line-numbers").css("white-space", "pre-wrap");
                });
            },
        }
    }
</script>

