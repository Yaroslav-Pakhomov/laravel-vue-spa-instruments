<script>

export default {

    /**
     * Название компонента
     */
    name: 'UserShowPage',

    /**
     * Подключенные компоненты
     */
    components: {},

    /**
     * Передаваемые св-ва от родителя и/или от контроллера
     */
    props: {
        user: Object,
    },

    /**
     * Св-ва компонента
     */
    data() {
        return {
            like_str: '',
        }
    },

    methods: {
        sendLike() {
            console.log(this.$page.props);
            console.log(this.$props);
            console.log(this.$props.user.id);
            axios.post(this.route('users.send-like', this.user.id), {from_id: this.$page.props.auth.user.id})
                .then(res => {
                    console.log(res);
                    this.like_str = res.data.like_str;
                })
                .catch(err => {
                    console.log(err);
                })
        },
    },
}

</script>

<template>

    <div class="my-6 mx-auto w-5/6">
        ID: {{ user.id }}
        <br>
        <br>
        name: {{ user.name }}
        <br>
        <br>
        email: {{ user.email }}
        <div class="mt-6" v-if="like_str">
            {{ like_str }}
        </div>
        <div class="mt-6">
            <button @click.prevent="sendLike()"
                    class="rounded-lg px-4 py-2 border-2 border-blue-500 text-blue-500 hover:bg-blue-600 hover:text-blue-100 duration-300">
                Send Like
            </button>
        </div>

    </div>
</template>

<style scoped>

</style>
