<script>

export default {
    /**
     * Название компонента
     */
    name: 'PostIndexPage',

    /**
     * Подключенные компоненты
     */
    components: {},

    /**
     * Передаваемые св-ва от родителя и/или от контроллера
     */
    props: {
        posts: Object,
    },

    /**
     * Св-ва компонента
     */
    data() {
        return {}
    },

    mounted() {

    },

    /**
     * Методы компонента
     */
    methods: {},

    computed: {},
}


</script>

<template>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div
                class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
            >
                <div class="p-6 text-gray-900">
                    You're logged in!
                </div>
            </div>
            <div class="shadow-lg rounded-lg overflow-hidden mx-4 md:mx-10">
                <table class="w-full table-fixed">
                    <thead>
                    <tr class="bg-gray-100">
                        <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">№ П/п</th>
                        <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">Заголовок</th>
                        <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">Описание</th>
                        <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">Ссылка картинки</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white">
                    <template v-for="(post, key, index) in posts" :key="post.id">
                        <tr>
                            <td class="py-4 px-6 border-b border-gray-200">{{ key + 1 }}</td>
                            <td class="py-4 px-6 border-b border-gray-200 truncate">{{ post.title }}</td>
                            <td class="py-4 px-6 border-b border-gray-200">{{ post.description }}</td>
                            <td class="py-4 px-6 border-b border-gray-200">
                                {{ post.image_url }}
                            </td>
                        </tr>
                    </template>

                    <!-- Add more rows here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</template>

<style scoped>

</style>
