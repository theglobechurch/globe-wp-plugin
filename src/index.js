import { registerPlugin } from '@wordpress/plugins';

import Fields from './js/sermon_fields';
import PodcastFields from './js/podcast_fields';

registerPlugin( 'glb-sermon-fields', {
  icon: null,
  render() {
    return( <Fields /> );
  }
} );

registerPlugin( 'glb-podcast-fields', {
  icon: null,
  render() {
    return( <PodcastFields /> );
  }
} );
