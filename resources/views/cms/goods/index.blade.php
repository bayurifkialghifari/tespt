<x-layouts.app>
    @php
        $title = 'Barang';
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
                            <x-new-th text="Nama" field="name" />
                            <x-new-th text="Harga" field="price" />
                            <x-new-th text="Gambar" field="image" />
                            <x-new-th text="Deskripsi" field="description" />
                            <x-new-th text="Stok" field="quantity" />
                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(value, index) in data">
                            <tr>
                                <td x-text="value.name"></td>
                                <td x-text="value.price"></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" x-on:click="showImage(value.image)" x-show="value.image != null">
                                        <i class="fa fa-eye"></i>
                                        Lihat Gambar
                                    </button>
                                </td>
                                <td x-text="value.description"></td>
                                <td x-text="value.quantity"></td>
                                <x-new-update-delete />
                            </tr>
                        </template>
                    </tbody>
                </table>

                <div class="float-end">
                    <x-new-pagination />
                </div>
            </div>
        </div>
        <x-acc-modal x-ref="showImageModal">
            <img src="" class="img-fluid" x-ref="showImage">
        </x-acc-modal>
        {{-- Create / Update Modal --}}
        <x-acc-modal x-ref="modal">
            <x-new-form x-on:submit.prevent="save">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" x-model="form.name" class="form-control" placeholder="Name">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" x-model="form.price" class="form-control" placeholder="Price">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <img src="" alt="logo" class="img-fluid" x-ref="reviewImage" x-show="form.image != ''">
                        <input type="file" x-on:change="uploadImage($event)" class="form-control" accept="image/*" x-ref="imageInput">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea x-model="form.description" class="form-control" placeholder="Description"></textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" x-model="form.quantity" class="form-control" placeholder="Quantity">
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
                    modalTitle: 'Buat Barang',
                    search: '',
                    sort: 'name',
                    order: 'desc',
                    form: {
                        name: '',
                        price: '',
                        image: '',
                        description: '',
                        quantity: '',
                    },
                    current_page: 1,
                    total_page: null,
                    baseUrl: '/goods/',
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
                    create() {
                        this.$refs.imageInput.value = null
                        this.isUpdate = false
                        this.modalTitle = 'Buat Barang'
                        new bootstrap.Modal(this.$refs.modal).show()

                        this.form = {
                            name: '',
                            price: '',
                            image: '',
                            description: '',
                            quantity: '',
                        }
                    },
                    update(id) {
                        this.$refs.imageInput.value = null
                        this.isUpdate = true
                        this.modalTitle = 'Update Barang'
                        new bootstrap.Modal(this.$refs.modal).show()


                        window.axios.get(`${this.baseUrl}${id}`).then((response) => {
                            this.form = response.data.data
                            this.$refs.reviewImage.src = `{{ url('/storage${this.baseUrl}${this.form.image}') }}`
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
                    showImage(url) {
                        new bootstrap.Modal(this.$refs.showImageModal).show()
                        this.$refs.showImage.src = `{{ url('/storage${this.baseUrl}${url}') }}`
                    },
                    uploadImage($event) {
                        const formData = new FormData
                        formData.append('save_to', 'goods')
                        formData.append('image', $event.target.files[0])

                        this.$refs.reviewImage.src = URL.createObjectURL($event.target.files[0])

                        window.axios.post('/service/upload-image', formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }).then(response => {
                            this.form.image = response.data.data.filename
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
