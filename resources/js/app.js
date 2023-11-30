import './bootstrap';
import Search from "./live-search";

// Instancier class Search uniquement si icone search présente dans l'entête donc si user connecté
if (document.querySelector('.header-search-icon')) {
    new Search()
}
