<x-layouts.app>
    @php
        $title = 'Pengambilan';
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="list-group list-group-horizontal mb-2">
                            <button class="list-group-item" x-on:click="filterStatus('all')" :class="status == 'all' ? 'active' : ''">
                                Semua
                            </button>
                            <button class="list-group-item" x-on:click="filterStatus('0')" :class="status == '0' ? 'active' : ''">
                                Menunggu Persetujuan
                            </button>
                            <button class="list-group-item" x-on:click="filterStatus('1')" :class="status == '1' ? 'active' : ''">
                                Disetujui
                            </button>
                            <button class="list-group-item" x-on:click="filterStatus('2')" :class="status == '2' ? 'active' : ''">
                                Ditolak
                            </button>
                        </div>
                    </div>
                </div>
                <x-new-header />
                <table class="table table-hover table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <x-new-th text="Staff" field="users.name" />
                            <x-new-th text="Total Barang" field="sells.total_items" />
                            <x-new-th text="Total Harga" field="sells.total_price" />
                            <x-new-th text="Tanggal Dibuat" field="sells.created_at" />
                            <x-new-th text="Status" field="sells.status" />
                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(value, index) in data">
                            <tr>
                                <td x-text="value.user_name"></td>
                                <td x-text="new Intl.NumberFormat('id').format(value.total_items)"></td>
                                <td x-text="new Intl.NumberFormat('id').format(value.total_price)"></td>
                                <td x-text="value.created_at"></td>
                                <td>
                                    <span x-show="value.status == 0" class="badge bg-warning">Menunggu Persetujuan</span>
                                    <span x-show="value.status == 1" class="badge bg-success">Disetujui</span>
                                    <span x-show="value.status == 2" class="badge bg-danger">Ditolak</span>
                                </td>
                                <x-new-update-delete x-show="value.status == 0" :edit="false">
                                    <a :href="`{{ route('cms.sell.detail') }}/${value.id}`" class="btn btn-primary">
                                        Detail Data
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
                    modalTitle: 'Buat {{ $title }}',
                    search: '',
                    sort: 'sells.created_at',
                    order: 'desc',
                    current_page: 1,
                    total_page: null,
                    baseUrl: '/sells/',
                    status: 'all',
                    data: [],
                    filterStatus(status) {
                        this.status = status

                        this.getAllData()
                    },
                    getAllData() {
                        const url = `${this.baseUrl}?sort=${this.sort}&order=${this.order}&search=${this.search}&page=${this.current_page}&status=${this.status}`

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
                    create() {
                        window.Swal.fire({
                            title: 'Apakah anda yakin ingin menambahkan data baru?',
                            text: 'Data ini akan ditambahkan',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.save()
                            }
                        })
                    },
                    save() {
                        window.axios.post(this.baseUrl, this.form).then(response => {
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
                        this.$watch('search', () => {
                            this.getAllData()
                        })
                    }
                }))
            })
        </script>
    </x-slot:scripts>
</x-layouts.app>
