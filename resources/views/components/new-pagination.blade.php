<nav aria-label="Page navigation" {{ $attributes }}>
    <ul class="pagination">
        {{-- <li class="page-item">
            <a class="page-link" href="#" x-show="previous_page">Previous</a>
        </li> --}}
        <template x-for="n in total_page">

            <li class="page-item" :class="n == current_page ? 'active' : ''">
                <a class="page-link" href="#" x-text="n" x-on:click="changePage(n)"></a>
            </li>
        </template>

        {{-- <li class="page-item">
            <a class="page-link" href="#" x-show="next_page">Next</a>
        </li> --}}
    </ul>
</nav>
