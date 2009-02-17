function togglePanels(panel)
{
  $$('.panel').each(function(item) {
                      if (item.id == panel) {
                        item.toggle();
                        $(item.id+"-link").toggleClassName('selected');
                      } else {
                        item.hide();
                        $(item.id+"-link").removeClassName('selected');
                      }
                    });
}
