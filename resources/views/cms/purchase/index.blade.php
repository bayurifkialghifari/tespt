<x-layouts.app>
    @php
        $title = 'Pembelian';
    @endphp
    <x-slot:title>
        {{ $title }}
    </x-slot:title>
    <h1 class="h3 mb-3">
        {{ $title ?? '' }}
    </h1>

    <div class="card" x-data="data">
        <div class="card-header">
            <h5 class="card-title">{{ $title ?? '' }} Data</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <x-new-header />
                <table class="table table-hover table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <x-new-th text="Staff" field="users.name" />
                            <x-new-th text="Barang" field="goods.name" />
                            <x-new-th text="Harga" field="purchases.price" />
                            <x-new-th text="Jumlah" field="purchases.quantity" />
                            <x-new-th text="Total" field="purchases.total" />
                            <x-new-th text="status" field="purchases.status" />
                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(value, index) in data">
                            <tr>
                                <td x-text="value.user_name"></td>
                                <td x-text="value.good_name"></td>
                                <td x-text="value.price"></td>
                                <td x-text="value.quantity"></td>
                                <td x-text="value.total"></td>
                                <td>
                                    <span x-show="value.status == 0" class="badge bg-warning">Menunggu Persetujuan</span>
                                    <span x-show="value.status == 1" class="badge bg-success">Disetujui</span>
                                    <span x-show="value.status == 2" class="badge bg-danger">Ditolak</span>
                                </td>
                                <x-new-update-delete x-show="value.status == 0" />
                            </tr>
                        </template>
                    </tbody>
                </table>

                <div class="float-end">
                    <x-new-pagination />
                </div>
            </div>
        </div>
        {{-- Create / Update Modal --}}
        <x-acc-modal x-ref="modal">
            <x-new-form x-on:submit.prevent="save">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Barang</label>
                        <select x-model="form.good_id" class="form-control">
                            <option value="">--Pilih Barang--</option>
                            <template x-for="(value, index) in goods">
                                <option :value="value.id" x-text="value.name"></option>
                            </template>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" x-model="form.price" class="form-control" placeholder="Harga" readonly>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" x-model="form.quantity" class="form-control" placeholder="Jumlah" x-on:input="calculateTotal">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Total</label>
                        <input type="number" x-model="form.total" class="form-control" placeholder="Total" readonly>
                    </div>
                </div>
            </x-new-form>
        </x-acc-modal>
    </div>

    <x-slot:scripts>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('data', () => ({
                    isUpdate: false,
                    modalTitle: 'Buat {{ $title }}',
                    search: '',
                    sort: 'users.name',
                    order: 'desc',
                    form: {
                        good_id: '',
                        price: '',
                        quantity: '',
                        total: '',
                    },
                    current_page: 1,
                    total_page: null,
                    baseUrl: '/purchase/',
                    data: [],
                    goods: [],
                    getAllGodds() {
                        window.axios.get('/good-like').then((response) => {
                            this.goods = response.data.data
                        })
                    },
                    getAllData() {
                        const url = `${this.baseUrl}?sort=${this.sort}&order=${this.order}&search=${this.search}&page=${this.current_page}`

                        window.axios.get(url).then((response) => {
                            const res = response.data.data
                            this.total_page = Math.floor(res.total / res.per_page)
                            this.current_page = res.current_page
                            this.data = res.data
                        })
                    },
                    changePage(page) {
                        this.current_page = page

                        this.getAllData()
                    },
                    changeOrder(sort, order) {
                        if (this.sort == sort) {
                            this.order = this.order == 'desc' ? 'asc' : 'desc'
                        }

                        this.sort = sort

                        this.getAllData()
                    },
                    calculateTotal() {
                        // If price is not empty
                        if(this.form.price) {
                            this.form.total = this.form.price * this.form.quantity
                        } else {
                            window.Toast.fire({
                                icon: 'error',
                                title: 'Barang belum dipilih.',
                            })

                            this.form.total = 0
                        }
                    },
                    create() {
                        this.isUpdate = false
                        this.modalTitle = 'Buat {{ $title }}'
                        new bootstrap.Modal(this.$refs.modal).show()

                        this.form = {
                            good_id: '',
                            price: '',
                            quantity: '',
                            total: '',
                        }
                    },
                    update(id) {
                        this.isUpdate = true
                        this.modalTitle = 'Update {{ $title }}'
                        new bootstrap.Modal(this.$refs.modal).show()


                        window.axios.get(`${this.baseUrl}${id}`).then((response) => {
                            const res = response.data.data

                            this.form.id = res.id
                            this.form.good_id = res.good_id
                            this.form.price = res.price
                            this.form.quantity = res.quantity
                            this.form.total = res.price * res.quantity
                        })
                    },
                    save() {
                        let request
                        if (this.isUpdate) {
                            request = window.axios.put(`${this.baseUrl}${this.form.id}`, this.form)
                        } else {
                            request = window.axios.post(this.baseUrl, this.form)
                        }

                        request.then(response => {
                            this.getAllData()
                            const modal = bootstrap.Modal.getInstance(this.$refs.modal)
                            modal.hide()
                            window.Toast.fire({
                                icon: 'success',
                                title: response.data.message
                            })
                        }).catch(error => {
                            window.Toast.fire({
                                icon: 'error',
                                title: error.response.data.message
                            })
                        })
                    },
                    confirmDelete(id) {
                        window.Swal.fire({
                            title: 'Apakah anda yakin?',
                            text: 'Data yang di hapus tidak dapat dikembalikan',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.delete(id)
                            }
                        })
                    },
                    delete(id) {
                        window.axios.delete(this.baseUrl + id).then((response) => {
                            this.getAllData()
                            window.Toast.fire({
                                icon: 'success',
                                title: response.data.message
                            })
                        }).catch(error => {
                            window.Toast.fire({
                                icon: 'error',
                                title: error.response.data.message
                            })
                        })
                    },
                    init() {
                        this.getAllData()
                        this.getAllGodds()
                        this.$watch('search', () => {
                            this.getAllData()
                        })
                        // Good id on change
                        this.$watch('form.good_id', () => {
                            if(this.form.good_id) {
                                window.axios.get(`goods/${this.form.good_id}`).then((response) => {
                                    const res = response.data

                                    this.form.price = res.data.price
                                    this.calculateTotal()
                                })
                            }
                        })
                    }
                }))
            })
        </script>
    </x-slot:scripts>
</x-layouts.app>
