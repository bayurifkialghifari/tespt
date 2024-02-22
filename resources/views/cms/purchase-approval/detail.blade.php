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
            <a href="{{ route('cms.purchase.approval') }}" class="btn btn-danger">
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
                <div class="col-md-12" x-show="detail.status == 0">
                    <div class="float-end">
                        <button
                            class="btn btn-success"
                            x-on:click="approve()"
                        >
                            <i class="align-middle" data-feather="check"></i>
                            Terima
                        </button>
                        <button
                            class="btn btn-danger"
                            x-on:click="reject()"
                        >
                            <i class="align-middle" data-feather="x"></i>
                            Tolak
                        </button>
                    </div>
                </div>
                <table class="table table-hover table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(value, index) in data">
                            <tr>
                                <td x-text="value.goods.name"></td>
                                <td x-text="new Intl.NumberFormat('id').format(value.quantity)"></td>
                                <td x-text="new Intl.NumberFormat('id').format(value.price)"></td>
                                <td x-text="new Intl.NumberFormat('id').format(value.total)"></td>
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
                        <label class="form-label">Status</label>
                        <input type="text" class="form-control" :value="isApprove ? 'Disetujui' : 'Ditolak'" readonly>
                    </div>
                </div>
                <div class="col-md-12" x-show="!isApprove">
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" x-model="form.reject_reason"></textarea>
                    </div>
                </div>
            </x-new-form>
        </x-acc-modal>
    </div>


    <x-slot:scripts>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('data', () => ({
                    isApprove: false,
                    modalTitle: 'Persetujuan Pembelian',
                    baseUrl: '/purchase/',
                    form: [],
                    data: [],
                    detail: {
                        user: [],
                        purchaseApprovals: {
                            code: '',
                        },
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
                    approve() {
                        this.isApprove = true
                        this.form = {
                            purchase_id: this.detail.id,
                            is_approved: 1,
                            reject_reason: '',
                        }
                        new bootstrap.Modal(this.$refs.modal).show()
                    },
                    reject() {
                        this.isApprove = false
                        this.form = {
                            purchase_id: this.detail.id,
                            is_approved: 0,
                            reject_reason: '',
                        }
                        new bootstrap.Modal(this.$refs.modal).show()
                    },
                    save() {
                        window.axios.post('/purchase-approval', this.form).then((response) => {
                            this.getAllData()
                            const modal = bootstrap.Modal.getInstance(this.$refs.modal)
                            modal.hide()
                            window.Toast.fire({
                                icon: 'success',
                                title: response.data.message
                            })
                        }).catch((error) => {
                            window.Toast.fire({
                                icon: 'error',
                                title: error.response.data.message
                            })
                        })
                    },
                    init() {
                        this.getAllData()
                    }
                }))
            })
        </script>
    </x-slot:scripts>
</x-layouts.app>
