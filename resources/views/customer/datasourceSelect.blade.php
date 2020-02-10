
<script>
    var datasources = @json($tables);
    var default_usage = @json($default_usage);
</script>

<!-- Modal -->
<div class="modal fade" id="datasourceSelectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Sources de données</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <select class="form-control" id="custom-selector">
            <option value="default">Défaut</option>
            <option value="custom">Personnalisé</option>
        </select>
        <hr/>
        <span id="default" class="ds">
            Cette option permet de renseigner des variables dans la partie "Edition", et d'appliquer la query par défaut définie par l'administrateur
        </span>
        @if (!empty($databases))
        <span id="custom" class="ds">
          <h6 class="text-muted">Les modifications apportées ne seront effectives qu'après une <b>mise à jour</b> du profil</h6>
          <form> 
            <div class="form-group">
                <label for="databaseSelected">Sources de données</label>
                <select class="form-control" id="databaseSelected">
                @foreach($databases as $d)
                    <option id="database-{{ $d->id }}" value="{{ $d->id }}">{{ $d->name }}</option>
                @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="queryTextArea">Query</label>
                <h6 class="text-muted">Pensez à ajouter des <b>limites</b> à la query si le nombre de résultats est important !</h6>
                <textarea class="form-control" id="queryTextArea" rows="3" placeholder="SELECT * id FROM TABLE"></textarea>
            </div>

             <h6 class="text-muted">
                Veillez à ce que les attributs ci-dessous soient bien tous renseignés :
             </h6>
            <div>
                <label for="queryTextArea">Attributs à renseigner : </label>
                <ul id="attributes-list">
                    <li>nom</li>
                </ul>
            </div>
          </form>
        </span>
        @else
        <p>Aucune sources de données trouvée, veuillez en <a href="/datasources" >ajouter une</a>.</p>
        @endif
        <div class="alert alert-warning fade show" id="msg-displayer" role="alert">
            <span id="msg-txt"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
        <button type="button" class="btn btn-primary" onClick="saveDatasource()">Sauvegarder</button>
      </div>
    </div>
  </div>
</div>
