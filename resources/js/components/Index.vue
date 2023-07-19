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
                                {{ currencyOption.charCode }} - ({{currencyOption.name}})
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <h2>Центральный банк Российской Федерации установил с {{ new Date(dateShow).toLocaleDateString('ru-RU') }} следующие курсы валют</h2>
                </div>
                <div class="row pb-4 pt-4">
                    <table class="table table-striped table-sm mt-4">
                        <thead>
                        <tr>
                            <th>Цифр. код</th>
                            <th>Букв. код</th>
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
        this.getTomorrowDate();
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
        },
        getTomorrowDate() {
            // Дата текущего дня.
            const today = new Date();

            today.setDate(today.getDate() + 1);

            this.dateDownload = today.toISOString().slice(0, 10)
            this.dateShow = today.toISOString().slice(0, 10)
        }
    },
}
</script>

<style scoped>

</style>
