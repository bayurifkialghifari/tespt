<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js" integrity="sha512-GWzVrcGlo0TxTRvz9ttioyYJ+Wwk9Ck0G81D+eO63BaqHaJ3YZX9wuqjwgfcV/MrB2PhaVX9DkYVhbFpStnqpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    window.axios = axios.create({
        baseURL: '{{ url('/api/v1') }}',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer {{ session('jwt_token') }}',
        }
    })

    window.Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    window.Swal = Swal
</script>
<script data-navigate-once="true">
    function sideBarCollapse() {
        const sidebar = document.getElementById('sidebar')
        const toogleSideBar = document.getElementsByClassName('sidebar-toggle')[0]

        toogleSideBar.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed')
        })
    }
    document.addEventListener("livewire:navigated", function() {
        sideBarCollapse()

        feather.replace()
    });

    document.addEventListener('livewire:initialized', () => {
        sideBarCollapse()

        Livewire.hook('morph.updated', ({ el, component }) => {
            feather.replace()
        })

        Livewire.on('alert', params => {
            window.Toast.fire({
                icon: params.type ?? 'success',
                title: params.message
            })
        })

        Livewire.on('confirm', params => {
            Swal.fire({
                title: params.title ?? 'Are you sure?',
                text: params.message ?? `You won't be able to revert this`,
                icon: params.icon ?? 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch(params.function, {id: params.id})
                }
            })
        })

        Livewire.on('closeModal', params => {
            const modal = document.getElementById(params.modal)
            const modalBackdrop = document.getElementsByClassName('modal-backdrop')[0]

            modal?.classList?.remove('show')
            modalBackdrop?.remove()
        })
    })
</script>
