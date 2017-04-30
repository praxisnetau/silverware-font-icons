<% if $Options %>
  <optgroup label="$Title.ATT">
    <% loop $Options %>
      <% include SilverWare\FontIcons\Forms\FontIconField\Option %>
    <% end_loop %>
  </optgroup>
<% else %>
  <option value="$Value.ATT"
  <% if $Unicode %> data-unicode="$Unicode.ATT"<% end_if %>
  <% if $Selected %> selected="selected"<% end_if %>
  <% if $Disabled %> disabled="disabled"<% end_if %>
  ><% if $Title %>$Title.XML<% else %>&nbsp;<% end_if %></option>
<% end_if %>
