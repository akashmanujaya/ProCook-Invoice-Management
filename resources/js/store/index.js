import { createStore } from 'vuex'

export default createStore({
  state: {
    invoices: []
  },
  mutations: {
    SET_INVOICES(state, invoices) {
      state.invoices = invoices;
    },
    ADD_INVOICE(state, invoice) {
      state.invoices.push(invoice);
    }
  },
  actions: {
    fetchInvoices({ commit }) {
      axios.get('/invoices')
        .then(response => {
          commit('SET_INVOICES', response.data.data);
        })
        .catch(error => console.error("Error fetching invoices:", error));
    },
    createInvoice({ commit }, invoiceData) {
      return axios.post('/invoices', invoiceData)
        .then(response => {
          commit('ADD_INVOICE', response.data.data);
          return response;
        });
    }
  },
  getters: {
    allInvoices: state => state.invoices
  }
});
