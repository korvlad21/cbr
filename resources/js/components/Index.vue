<template>
<div class="container">
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Курсы валют ЦБ РФ</div>
            <div class="card-body">
                <div class="row pb-4 pt-4">
                    <div class="col-4">
                        <span>Дата</span>
                        <input
                            v-model="date"
                            type="date"
                            class="form-control"
                            @change="getExchangeRates"
                        />
                    </div>
                    <div class="col-4">
                        <span>Выбор Валюты</span>
                        <select
                            v-model="currency"
                            class="form-control"
                            @change="getExchangeRates"
                        >
                            <option
                                v-for="currencyOption in currencyOptions"
                                :value="currencyOption.charCode"
                                :key="currencyOption.charCode"
                            >
                                {{ currencyOption.charCode }} - ({{currencyOption.name}})
                            </option>
                        </select>
                    </div>
                    <div class="col-4 d-flex flex-column">
                        <button
                            class="btn btn-dark mt-auto"
                            @click="downloadExchange"
                        >
                            Загрузить курс валют по определённой дате
                        </button>
                    </div>
                </div>
                <div v-if="0 < exchangeRates.length" class="row pb-4 pt-4">
                    <div class="row">
                        <h2>Центральный банк Российской Федерации установил с {{ new Date(date).toLocaleDateString('ru-RU') }} следующие курсы валют</h2>
                    </div>
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
                            <tr
                                v-for="exchangeRate in exchangeRates"
                            >
                                <th>{{exchangeRate['numCode']}}</th>
                                <th>{{exchangeRate['charCode']}}</th>
                                <th>{{exchangeRate['name']}}</th>
                                <th>
                                    {{exchangeRate['rate']}} ({{(0 < exchangeRate['difference']) ? '+' : ''}}{{exchangeRate['difference']}})
                                </th>

                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="row pb-4 pt-4">
                    <h2>Для просмотра курсов на {{this.date}} укажите дату и загрузите</h2>
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
            maxDate: '',
            currency: 'RUB',
            currencyOptions: [],
            exchangeRates: [],
        };
    },
    mounted(){
        this.getCurrenciesOptions();
        this.getTomorrowDate();
        this.getExchangeRates();
    },
    methods: {
        downloadExchange() {
            axios.post('/api/exchange/download', {
                date: this.date
            })
                .then(({data}) => {
                    this.getExchangeRates()
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        getExchangeRates() {
            axios.post('/api/exchange/get_rates', {
                date: this.date,
                currency: this.currency
            })
                .then(({data}) => {
                    console.log(data)
                    this.exchangeRates = data.exchangeRates
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
            const today = new Date();
            today.setDate(today.getDate() + 1);
            this.date= today.toISOString().slice(0, 10)
        },
    },
}
</script>

<style scoped>

</style>
