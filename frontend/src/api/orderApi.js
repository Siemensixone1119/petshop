import { api } from './http'

export const orderApi = {
    getAll() {
        return api.get('/orders')
    },

    create() {
        return api.post('/orders')
    },

    getById(id) {
        return api.get(`/orders/${id}`)
    },
}