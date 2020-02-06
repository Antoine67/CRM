<button class="btn edit-button" data-toggle="modal" data-target="#datasourceSelectModal" onClick="openModal('{{ $table_name }}', this)">
    <i class="fas fa-edit" style="margin-right:2px"></i>
    <span class="query-text" hidden>{{ $query }}</span>
    Editer
</button>

<style>

.edit-button {
    position: absolute;
    top:0;
    right:0;
}
</style>
