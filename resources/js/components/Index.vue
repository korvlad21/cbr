<template>
<div class="container">
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Курсы валют ЦБ РФ</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <span>Дата получение курса</span>
                        <input v-model="date" type="date" class="form-control" />
                    </div>
                    <div class="col-3"><button
                        class="btn btn-dark"
                        @click="downloadExchange"
                    >
                        Загрузить курс валют по определённой дате
                    </button></div>
                    <div class="col-3"><button
                        class="btn btn-dark"
                        @click="downloadExchange"
                    >
                        Загрузить курс валют за 180 дней
                    </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>

</template>

<script>
export default {
    name: "Index",
    data() {
        return {
            date: '',
        };
    },
    methods: {
        downloadExchange() {
            axios.post('/api/exchange/download', {
                date: this.date
            })
                .then(({data}) => {
                    console.log(data)
                })
                .catch((error) => {
                    console.error(error);
                });
        },
    },
}
</script>

<style scoped>

</style>
