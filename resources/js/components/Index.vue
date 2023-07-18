<template>
<div class="container">
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Курсы валют ЦБ РФ</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <span>Дата для загрузки курса</span>
                        <input v-model="dateDownload" type="date" class="form-control" />
                    </div>
                    <div class="col-4 d-flex flex-column">
                        <button
                            class="btn btn-dark mt-auto"
                            @click="downloadExchange"
                        >
                            Загрузить курс валют по определённой дате
                        </button>
                    </div>
                    <div class="col-3 d-flex flex-column">
                        <button
                            class="btn btn-dark mt-auto"
                            @click="downloadExchange"
                        >
                            Загрузить курс валют за 180 дней
                        </button>
                    </div>
                </div>
                <div class="row pb-4 pt-4">
                    <div class="col-4">
                        <span>Дата для отображения курса</span>
                        <input v-model="dateShow" type="date" class="form-control" />
                    </div>
                    <div class="col-4">
                        <span>Выбор Валюты</span>
                        <select v-model="currency" class="form-control">
                            <option
                                v-for="currencyOption in currencyOptions"
                                :value="currencyOption.charCode"
                                :key="currencyOption.charCode"
                            >
                                {{ currencyOption.charCode }}
                            </option>
                        </select>
                    </div>
                    <div class="col-3 d-flex flex-column">
                        <button
                        class="btn btn-primary mt-auto"
                        @click="downloadExchange"
                    >
                        Отобразить
                    </button>
                    </div>
                </div>
                <div class="row pb-4 pt-4">
                    <table class="table table-striped table-sm mt-4">
                        <thead>
                        <tr>
                            <th>Цифр. код</th>
                            <th>Букв. код</th>
                            <th>Единиц</th>
                            <th>Валюта</th>
                            <th>Курс</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>

                            </tr>
                        </tbody>
                    </table>
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
            dateDownload: '',
            dateShow: '',
            currency: 'RUB',
            currencyOptions: [],
        };
    },
    mounted(){
        this.getCurrenciesOptions();
    },
    methods: {
        downloadExchange() {
            axios.post('/api/exchange/download', {
                date: this.dateDownload
            })
                .then(({data}) => {
                    console.log(data)
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        getExchange() {
            axios.post('/api/exchange/get', {
                date: this.dateShow
            })
                .then(({data}) => {
                    console.log(data)
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        getCurrenciesOptions() {
            axios.post('/api/currency/get')
                .then(({data}) => {
                    this.currencyOptions = data.currencyOptions
                    console.log(data)
                })
                .catch((error) => {
                    console.error(error);
                })
                .finally(() => {
                });
        }
    },
}
</script>

<style scoped>

</style>
