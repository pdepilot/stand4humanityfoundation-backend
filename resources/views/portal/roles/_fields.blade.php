<div class="settings-field">
    <label for="name">Role name</label>
    <input id="name" name="name" type="text" value="{{ old('name', isset($role) ? $role->name : '') }}" required maxlength="80">
</div>
<fieldset class="settings-field">
    <legend>Pages this role may open</legend>
    @foreach ($permissions as $permission)
        <label for="permission-{{ $permission->value }}">
            <input id="permission-{{ $permission->value }}" name="permissions[]" type="checkbox" value="{{ $permission->value }}" @checked(in_array($permission->value, old('permissions', isset($role) ? $role->permissions : []), true))>
            {{ $permission->label() }}
        </label>
    @endforeach
</fieldset>
