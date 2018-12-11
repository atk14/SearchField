SearchField
===========

SearchField is a field for entering searching terms into forms in ATK14 applications.

Basically it renders ```<input type="search">``` element.

Installation
------------

Just use the Composer:

    cd path/to/your/atk14/project/
    composer require atk14/search-field

Optionally you can symlink the SearchField files into your project:

    ln -s ../../vendor/atk14/search-field/src/app/fields/search_field.php app/fields/search_field.php
    ln -s ../../vendor/atk14/search-field/src/app/widgets/search_widget.php app/widgets/search_widget.php

Usage in a ATK14 application
----------------------------

In a form:

    <?php
    // file: app/forms/articles/index_form.php
    class IndexForm extends ApplicationForm {

      function set_up(){
        $this->add_field("q", new SearchField([
          "label" => "Search",
          "max_length" => 200,
          "required" => false, 
        ]));

      }
    }

In a controller:

    <?php
    // file: app/controllers/articles_controller.php
    class ArticlesController extends ApplicationController {
      
      function index(){
        $this->page_title = "Listing Articles";

        ($d = $this->form->validate($this->params)) || ($d = $this->form->get_initial());

        $conditions = $bind_ar = [];
        $conditions[] = "published_at<NOW()";

        if($d["q"]){
          $ft_cond = FullTextSearchQueryLike::GetQuery("UPPER(title||' '||body)",mb_strtoupper($d["q"]),$bind_ar);
          if($ft_cond){
            $conditions[] = $ft_cond;
          }
        }

        $this->tpl_data["finder"] = Article::Finder([
          "conditions" => $conditions,
          "bind_ar" => $bind_ar,
          "limit" => 20,
          "offset" => $this->params->getInt("offset"),
          "order_by" => "published_at DESC, id DESC",
        ]);
      }
    }

In the example above the FullTextSearchQueryLike class is used. It provides full-text-searching-like-feeling with just SQL operator LIKE. You can install it using the Composer:

    composer required yarri/full-text-search-query-like

License
-------

SearchField is free software distributed [under the terms of the MIT license](http://www.opensource.org/licenses/mit-license)
