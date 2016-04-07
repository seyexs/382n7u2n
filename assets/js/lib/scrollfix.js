Ext.require('Ext.grid.Scroller',
    function() {
        Ext.override(Ext.grid.Scroller, {


          afterRender: function() {
            var me = this;
            me.callParent();
            me.mon(me.scrollEl, 'scroll', me.onElScroll, me);
            Ext.cache[me.el.id].skipGarbageCollection = true;
            // add another scroll event listener to check, if main listeners is active
            Ext.EventManager.addListener(me.scrollEl, 'scroll', me.onElScrollCheck, me);
            Ext.cache[me.scrollEl.id].skipGarbageCollection = true;
          },


          // flag to check, if main listeners is active
          wasScrolled: false,


          // synchronize the scroller with the bound gridviews
          onElScroll: function(event, target) {
            this.wasScrolled = true; // change flag -> show that listener is alive
            this.fireEvent('bodyscroll', event, target);
          },


          // executes just after main scroll event listener and check flag state
          onElScrollCheck: function(event, target, options) {
            // var me = event.data.scope;
            var me = this;


            if (!me.wasScrolled) {
//              Altus.Logging.info('Re-adding event listener for scroll');
              // Achtung! Event listener was disappeared, so we'll add it again
              me.mon(me.scrollEl, 'scroll', me.onElScroll, me);
            }
            me.wasScrolled = false; // change flag to initial value
          }


        });
    }
);