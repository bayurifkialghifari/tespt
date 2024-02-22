<x-layouts.app>
    @php
        $title = 'Pembelian Persetujuan';
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
                <x-new-header :isCreate="false" />
                <table class="table table-hover table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <x-new-th text="Kode" field="purchase_approvals.code" />
                            <x-new-th text="Staff" field="users.name" />
                            <x-new-th text="Total Barang" field="purchases.total_items" />
                            <x-new-th text="Total Harga" field="purchases.total_price" />
                            <x-new-th text="Tanggal Dibuat" field="purchases.created_at" />
                            <x-new-th text="Status" field="purchases.status" />
                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(value, index) in data">
                            <tr>
                                <td x-text="value.approval_code ?? '-'"></td>
                                <td x-text="value.user_name"></td>
                                <td x-text="new Intl.NumberFormat('id').format(value.total_items)"></td>
                                <td x-text="new Intl.NumberFormat('id').format(value.total_price)"></td>
                                <td x-text="value.created_at"></td>
                                <td>
                                    <span x-show="value.status == 0" class="badge bg-warning">Menunggu Persetujuan</span>
                                    <span x-show="value.status == 1" class="badge bg-success">Disetujui</span>
                                    <span x-show="value.status == 2" class="badge bg-danger">Ditolak</span>
                                </td>
                                <x-new-update-delete x-show="value.status == 0" :edit="false" :delete="false">
                                    <a :href="`{{ route('cms.purchase.approval.detail') }}/${value.id}`" class="btn btn-primary">
                                        <i class="fas fa-eye"></i>
                                        Ganti Status
                                    </a>
                                </x-new-update-delete>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <div class="float-end">
                    <x-new-pagination />
                </div>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('data', () => ({
                    isUpdate: false,
                    modalTitle: 'Ganti Status Pembelian',
                    search: '',
                    sort: 'purchases.created_at',
                    order: 'desc',
                    current_page: 1,
                    total_page: null,
                    baseUrl: '/purchase/',
                    data: [],
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
                    init() {
                        this.getAllData()
                        this.$watch('search', () => {
                            this.getAllData()
                        })
                    }
                }))
            })
        </script>
    </x-slot:scripts>
</x-layouts.app>
