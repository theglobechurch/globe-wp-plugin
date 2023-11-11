import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { compose } from '@wordpress/compose';
import { withSelect, withDispatch } from '@wordpress/data';
import { Button } from '@wordpress/components';
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';

const PodcastFields = ( { postType, postMeta, setPostMeta, podcastUrl } ) => {

  if (postType !== 'podcast') {
    return null;
  }

  let audioUrl = null;
  if (podcastUrl) {
    audioUrl = podcastUrl.source_url;
  }

  return (
    <PluginDocumentSettingPanel title='Episode file' initialOpen="true">

      <MediaUploadCheck>
        <MediaUpload
          onSelect={ ( media ) => {
              setPostMeta( { glb_podcast_mp3: media.id.toString() } )
              audioUrl = media.url;
            }
          }
          allowedTypes={ 'audio' }
          value={ postMeta.glb_podcast_mp3 }
          render={ ( { open } ) => (
            <div>
              { audioUrl ?
                <div>
                  <p>Episode Mp3</p>
                  <audio src={audioUrl} controls preload="metadata"></audio>
                  <Button isSecondary onClick={ open }>Replace mp3</Button>
                </div>
                :
                <div>
                  <p>Select the episode to upload</p>
                  <Button isPrimary onClick={ open }>Select mp3</Button>
                </div>
              }
            </div>
          )}
        />
      </MediaUploadCheck>
    </PluginDocumentSettingPanel>
  );
}

export default compose( [
  withSelect( ( select ) => {
    const meta = select( 'core/editor' ).getEditedPostAttribute( 'meta' );
    const podcast = meta.glb_podcast_mp3;

    return {
      postMeta: meta,
      postType: select( 'core/editor' ).getCurrentPostType(),
      podcastUrl: select('core').getMedia(podcast),
    };
  } ),
  withDispatch( ( dispatch ) => {
    return {
      setPostMeta( newMeta ) {
        dispatch( 'core/editor' ).editPost( { meta: newMeta } );
      }
    };
  } )
] )(PodcastFields);


