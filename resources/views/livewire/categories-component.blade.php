<div>
    <div>
        <div>
            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('message') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        <h1>Categorías</h1>
        <form wire:submit.prevent='save' id="form">
            <div class="mb-2">
                <label><b>Nombre: </b></label>
                <input type="text" class="form-control form-control-sm" wire:model="name" required>
                @error('title')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-2">
                <label><b>Position: </b></label>
                <input type="number" min="0" class="form-control form-control-sm" wire:model='position' />
                @error('description')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-2">
                <label><b>Imagen: </b></label>
                @if ($editMode)
                    <input type="file" class="form-control form-control-sm" wire:model="image">
                @else
                    <input type="file" class="form-control form-control-sm" wire:model="image" required>
                @endif
                <div wire:loading wire:target="image">Uploading...</div>
                @error('image')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-2">
                <label><b>Principal: </b></label>
                <input type="checkbox" wire:model="main">
                @error('status')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mt-2 text-center">
                <button type="button" class="btn btn-secondary"
                    wire:click="resetear">{{ $editMode ? 'Cancelar' : 'Reset' }}</button>
                <button type="submit" class="btn btn-primary">{{ $editMode ? 'Actualizar' : 'Crear' }}</button>
            </div>
        </form>
    </div>
    <div>
        <h1>Registros</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-dense table-sm">
                <thead class="bg-dark text-white">
                    <th>Nombre</th>
                    <th>Posición</th>
                    <th>Principal</th>
                    <th>Imagen</th>
                    <th class="text-center"><i class="fas fa-wrench"></i></th>
                </thead>
                <tbody>
                    @if (count($categories) > 0)
                        @foreach ($categories as $cat)
                            <tr>
                                <td>{{ $cat->name }}</td>
                                <td>{{ $cat->position }}</td>
                                <td class="text-center">
                                    @if ($cat->main)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center"><img src="{{ asset('storage/categories/' . $cat->image) }}"
                                        alt="{{ $cat->name }}" style="width: auto; height: 80px;"></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info" wire:click='setEditMode({{ $cat->id }})'>
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="setId({{ $cat->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">
                                No hay registros
                            </td>
                        </tr>
                    @endif
                </tbody>
                {{ $categories->links() }}
            </table>
            {{ $categories->links() }}
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <h5><b>¿Quieres continuar con el proceso de eliminación?</b></h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</div>
