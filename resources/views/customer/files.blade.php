@editor
<?php
$query = "";
if( isset ($datasources) ) {
    foreach ($datasources as $d) {
        if( strcmp($d->table_associated, 'files') == 0 ) {
            $query = $d->query;
        }
    }
}
?>
@include('customer.buttonDatasourceSelect', ['table_name' => 'files',  'query' => $query]) 
@endeditor

<!-- Files -->
<h5>Fichiers :</h5>

@isset($files)
@foreach($customer->getAssociatedFiles() as $file)
<div class="align-middle d-inline-block" onClick="openUrl(' {{ $file['path'] }} ')">
                                    
    <div class="form-group">
        <input type="checkbox" name="fancy-checkbox-success" id="fancy-checkbox-success" autocomplete="off" checked="true" disabled />
        <div class="btn-group">
            <label for="fancy-checkbox-success" class="btn btn-success">
                <span class="fas fa-file"></span>
                <span> </span>
            </label>
            <label for="fancy-checkbox-success" class="btn btn-default active">
                {{ $file['givenName'] }}
            </label>
        </div>
    </div>
                                    
</div>
@endforeach
@else
<p class="font-italic mb-0 text-muted">Aucun fichier trouvé</p>
@endif

@if (false)
<!-- SharePoint folders -->
<h5>Dossiers SharePoint :</h5>
<h6 class="text-muted"><a href="#" onClick="openUrl('{{ $customer->getProperties()['mainFolderWebUrl'] }}')">Dossier client</a></h6>
<ul class="fa-ul">
@foreach($customer->getFolders() as $folder)
    <li><a href="#" onClick="openUrl('{{ $folder->getWebUrl() }}')">
        <span class="fa-li"><i class="fas fa-folder"></i></span>
        {{ $folder->getName() }} - <i>{{ $folder->getChildCount() }} fichier(s)</i>
    </a></li>
@endforeach
</ul>
<h6 class="text-muted"><a href="#" onClick="openUrl('{{ $customer->getProperties()['extranetFolderWebUrl'] }}');">Dossier extranet</a></h6>
<ul class="fa-ul">
@foreach($customer->getExtranetFolders() as $folder)
    <li><a href="#" onClick="openUrl('{{ $folder->getWebUrl() }}')">
        <span class="fa-li"><i class="fas fa-folder"></i></span>
        {{ $folder->getName() }} - <i>{{ $folder->getChildCount() }} fichier(s)</i>
    </a></li>
@endforeach
</ul>
@endif
