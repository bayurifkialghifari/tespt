<div>
    <form {{ $attributes }}>
        <div class="container">
            <div class="row">
                {{ $slot }}
            </div>
        </div>

        @if (isset($actions))
            <div class="row">
                {{ $actions }}
            </div>
        @else
            <div class="row">
                <div class="col-md-12">
                    <div class="float-end">
                        <button
                            class="btn btn-success"
                            type="submit">
                            <i class="align-middle" data-feather="save"></i>
                            Save
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </form>
</div>
