<x-layouts.app>
    @php
        $title = 'Pembelian Detail';
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
            <a href="{{ route('cms.purchase') }}" class="btn btn-danger">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12" x-show="detail.status == 1">
                    <div class="mb-3">
                        <label class="form-label">Kode</label>
                        <input type="text" class="form-control" x-model="detail.purchaseApprovals.code" readonly />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Staff</label>
                        <input type="text" class="form-control" x-model="detail.user.name" readonly />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Dibuat</label>
                        <input type="text" class="form-control" x-model="detail.created_at" readonly />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <input type="text" class="form-control" x-model="detail.statusText" readonly />
                    </div>
                </div>
                <div class="col-md-12" x-show="detail.status == 2">
                    <div class="mb-3">
                        <label class="form-label">Alasan Ditolak</label>
                        <input type="text" class="form-control" x-model="detail.reject_reason" readonly />
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <x-new-header :isSearch="false" x-show="detail.status == 0" />
                <table class="table table-hover table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Total</th>
                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(value, index) in data">
                            <tr>
                                <td x-text="value.goods.name"></td>
                                <td x-text="new Intl.NumberFormat('id').format(value.quantity)"></td>
                                <td x-text="new Intl.NumberFormat('id').format(value.price)"></td>
                                <td x-text="new Intl.NumberFormat('id').format(value.total)"></td>
                                <x-new-update-delete x-show="detail.status == 0" />
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Create / Update Modal --}}
        <x-acc-modal x-ref="modal">
            <x-new-form x-on:submit.prevent="save">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Barang</label>
                        <select x-model="form.good_id" class="form-control">
                            <option value="">--Select Goods--</option>
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
                        <input type="number" x-model="form.quantity" class="form-control" placeholder="Jumlah" x-on:input="calculateTotal()">
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
                    baseUrl: '/purchase/',
                    form: [],
                    data: [],
                    detail: {
                        user: [],
                        purchaseApprovals: {
                            code: '',
                        },
                    },
                    goods: [],
                    getAllGoods() {
                        window.axios.get('/good-like').then((response) => {
                            this.goods = response.data.data
                        })
                    },
                    getAllData() {
                        const url = `${this.baseUrl}{{ request()->route('id') }}`

                        window.axios.get(url).then((response) => {
                            const res = response.data.data

                            this.detail = res
                            this.detail.statusText = res.status == 0 ? 'Menunggu Persetujuan' : res.status == 1 ? 'Disetujui' : 'Ditolak'
                            this.detail.purchaseApprovals = {
                                code: res?.purchase_approvals?.code ?? '',
                            }
                            this.data = res.purchase_details
                        })
                    },
                    calculateTotal() {
                        this.form.total = this.form.price * this.form.quantity
                    },
                    create() {
                        this.isUpdate = false
                        this.modalTitle = 'Buat {{ $title }}'
                        new bootstrap.Modal(this.$refs.modal).show()

                        this.form = {
                            purchase_id: '{{ request()->route('id') }}',
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


                        window.axios.get(`purchase-detail/${id}`).then((response) => {
                            this.form = response.data.data
                        })
                    },
                    save() {
                        let request
                        if (this.isUpdate) {
                            request = window.axios.put(`purchase-detail/${this.form.id}`, this.form)
                        } else {
                            request = window.axios.post('purchase-detail', this.form)
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
                        window.axios.delete('purchase-detail/' + id).then((response) => {
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
                        this.getAllGoods()
                        this.$watch('form.good_id', () => {
                            if(this.form.good_id) {
                                window.axios.get(`/goods/${this.form.good_id}`).then((response) => {
                                    this.form.price = response.data.data.price

                                    // calculate total
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
