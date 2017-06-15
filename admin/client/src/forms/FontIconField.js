/* Font Icon Field
===================================================================================================================== */

import $ from 'jquery';

$.entwine('silverware.fonticonfield', function($) {
  
  // Handle Font Icon Field Select:
  
  $('.field.fonticonfield select').entwine({
    
    onmatch: function() {
      
      var $select = $(this);
      
      $select.on('chosen:hiding_dropdown', function() {
        $select.trigger('change');
      });
      
      this._super();
      
    },
    
    onchange: function(e) {
      
      var icon = this.getSelectedIcon();
      
      if (icon) {
        this.getChosenContainer().find('a.chosen-single').setIcon(icon);
      }
      
      this._super(e);
      
    },
    
    getTagName: function() {
      return $(this).data('tag');
    },
    
    getTagClasses: function(value) {
      return $(this).data('classes').replace('{value}', value);
    },
    
    getChosenContainer: function() {
      return $(this).next();
    },
    
    getSelectedIcon: function() {
      return $(this).find('option:selected').val();
    },
    
    getIconTag: function(value) {
      var tag = document.createElement(this.getTagName());
      tag.className = this.getTagClasses(value);
      return tag.outerHTML + ' ';
    }
    
  });
  
  // Handle Font Icon Field Selected Item:
  
  $('.field.fonticonfield a.chosen-single').entwine({
    
    onmatch: function() {
      this.setIcon(this.getSelect().getSelectedIcon());
      this._super();
    },
    
    setIcon: function(icon) {
      if (!this.hasIcon() && icon) {
        $(this).find('span').prepend(this.getSelect().getIconTag(icon));
      }
    },
    
    hasIcon: function() {
      return $(this).find('span > ' + this.getSelect().getTagName()).length > 0;
    },
    
    getSelect: function() {
      return $(this).closest('.field.fonticonfield').find('select');
    }
    
  });
  
  // Handle Font Icon Field Available Items:
  
  $('.field.fonticonfield ul.chosen-results > li.group-option').entwine({
    
    onmatch: function() {
      
      var idx  = $(this).data('option-array-index');
      var icon = this.getChosen().results_data[idx].value;
      
      $(this).prepend(this.getSelect().getIconTag(icon));
      
      this._super();
      
    },
    
    getSelect: function() {
      return $(this).closest('.field.fonticonfield').find('select');
    },
    
    getChosen: function() {
      return this.getSelect().data('chosen');
    }
    
  });
  
});
