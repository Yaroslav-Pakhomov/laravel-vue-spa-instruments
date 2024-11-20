<script>

export default {
    /**
     * Название компонента
     */
    name: 'CreatePage',

    /**
     * Подключенные компоненты
     */
    components: {},

    /**
     * Передаваемые св-ва от родителя и/или от контроллера
     */
    props: {
        post: Object,
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
            <div class="flex items-center justify-center p-12">
                <!-- Author: FormBold Team -->
                <div class="mx-auto w-full max-w-[550px] bg-white">
                    <form>
                        <div class="mb-5">
                            <label for="name" class="mb-3 block text-base font-medium text-[#07074D]">
                                Title
                            </label>
                            <input type="text" name="name" id="name" placeholder="Title"
                                   class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                        </div>
                        <div class="mb-5">
                            <label for="phone" class="mb-3 block text-base font-medium text-[#07074D]">
                                Description
                            </label>
                            <input type="text" name="phone" id="phone" placeholder="Enter your Description"
                                   class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                        </div>
                        <div class="mb-6">
                            <label for="image" class="block text-lg font-medium text-gray-800 mb-1">Image</label>
                            <input type="file" id="image" name="image" accept="image/*" class="w-full">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="border-4 border-sky-500 px-6 py-2 bg-indigo-500 text-white font-semibold rounded-md hover:bg-indigo-600 focus:outline-none">
                                Отправить
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>


</template>

<style scoped>

</style>
