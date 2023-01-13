require('./bootstrap');

let instance = window.axios.create({
    baseURL: 'http:127.0.0.1:8000/api/',
    timeout: 1000,
});

export function exportToExcel() {
    console.log(this);
    // instance.post('/export', )
}