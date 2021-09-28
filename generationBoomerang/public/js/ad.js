

        $('#add-image').click(function(){
            // je recupére le numéro des futurs champs que je vais créer
            const index = +$('#widgets-counter').val();

            // je recupère le protype des entrées
            const tmpl =$('#ad_images').data('prototype').replace(/__name__/g,index);
            // j injecte ce code ausein de la div
            $('#ad_images').append(tmpl);

            $('#widgets-counter').val(index + 1);    

            // je gere lebtn supprimer
            handleDeleteButtons();
        });
        function updateCounter(){
            const count= +$('#ad_images div.form-group').length;
            $('#widgets-counter').val(count);
        }

        updateCounter();
    
        function handleDeleteButtons(){
            $('button[data-action="delete"]').click(function(){
                const target = this.dataset.target;
                $(target).remove();
            });
        }



