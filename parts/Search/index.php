<form
  role="search"
  method="get"
  id="searchform"
  class="searchform"
  action="<?php echo home_url('/'); ?>"
>
  <input
    type="text"
    value=""
    name="s"
    id="s"
    value="<?php echo get_search_query(); ?>"
    placeholder="Faça sua busca aqui"
  />
  <button type="submit" id="searchsubmit" class="search-icon">
    <i class="bi bi-search"></i>
  </button>
</form>
