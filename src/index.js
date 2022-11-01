import { registerPlugin } from '@wordpress/plugins';

import Fields from './js/sermon_fields';

registerPlugin( 'glb-sermon-fields', {
  icon: null,
  render() {
    return( <Fields /> );
  }
} );
