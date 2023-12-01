import './bootstrap';
import Search from "./live-search";
import Chat from "./chat";

// Instancier class Search uniquement si icone search présente dans l'entête donc si user connecté
if (document.querySelector('.header-search-icon')) {
    new Search()
}

// Instancier class Chat uniquement si icone chat présent dans l'entête donc si user connecté
if (document.querySelector('.header-chat-icon')) {
    new Chat();
}
