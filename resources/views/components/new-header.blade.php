@props(['isCreate' => true, 'isSearch' => true])

<div>
    <div class="row mb-3">
        @if($isSearch)
            <div class="col-md-{{ $isCreate ? '6' : '12' }}">
                <div class="input-group">
                    <span class="input-group-text" id="search-icon">
                        <i class="align-middle" data-feather="search"></i>
                    </span>
                    <input x-model="search" type="text" class="form-control" aria-label="Search" aria-describedby="search-icon" placeholder="Search...">
                </div>
            </div>
        @endif
        @if($isCreate)
            <div class="col-md-{{ $isSearch ? '6' : '12' }}">
                <div class="float-end">
                    <button
                        class="btn btn-success"
                        x-on:click="create()"
                    >
                        <i class="align-middle" data-feather="plus-circle"></i>
                        Create
                    </button>
                </div>
            </div>
        @endif
        {{ $slot->isEmpty() ? '' : $slot }}
    </div>
</div>
