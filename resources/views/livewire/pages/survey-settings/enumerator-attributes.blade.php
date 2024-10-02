<div class="">

    <x-settings.edit-enumerator-attribute-modal
        wire:key="edit-enumerator-attribute-modal"
        :modal-open="$editEnumeratorAttributeModalOpen"
        :field-type="$editFieldType"
        :is-required="$editIsRequired"
        :options="$editOptions"
    />

    <x-settings.delete-enumerator-attribute-modal
        wire:key="delete-enumerator-attribute-modal"
        :modal-open="$deleteEnumeratorAttributeModalOpen"
        :id="$editingAttributeId"
    />

    <div class="w-full overflow-x-scroll border">
        <table class="app-table">
            <thead>
            <tr class="text-left">
                <th>Name</th>
                <th>Order</th>
                <th>Display Text</th>
                <th>Field Type</th>
                <th>Is Required</th>
                <th></th>
            </tr>
            </thead>

            <tbody>
                <tr>
                    <td>Enum_Code</td>
                    <td>1</td>
                    <td>Enumerator Code</td>
                    <td>Text</td>
                    <td>Yes</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td>2</td>
                    <td>Name</td>
                    <td>Text</td>
                    <td>Yes</td>
                    <td></td>
                </tr>
                @foreach($attributes as $attribute)
                <tr>
                    <td>{{ $attribute->name }}</td>
                    <td>{{ $attribute->order }}</td>
                    <td>{{ $attribute->display_text }}</td>
                    <td>{{ $attribute->field_type }}</td>
                    <td>{{ $attribute->is_required == 1 ? "Yes" : "No" }}</td>
                    <td>
                        <div class="flex flex-row gap-3">
                            <button
                                wire:click="toggleEditEnumeratorAttributeModal({{ $attribute->id }})"
                                class="btn btn-info">
                                Edit
                            </button>
                            <button
                                wire:click="togglDeleteEnumeratorAttributeModal({{ $attribute->id }})"
                                class="btn bg-red-500 text-white">
                                Delete
                            </button>
                        </div>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
