import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { compose } from '@wordpress/compose';
import { withSelect, withDispatch } from '@wordpress/data';
import { TextControl, FormFileUpload, Button } from '@wordpress/components';
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';

const Fields = ( { postType, postMeta, setPostMeta, sermonUrl } ) => {

  let audioUrl = null;
  if (sermonUrl) {
    audioUrl = sermonUrl.source_url;
  }

  return (
    <PluginDocumentSettingPanel title='Sermon files' initialOpen="true">
      <TextControl
          label='Sermon Passage'
          value={ postMeta.glb_sermon_passage}
          onChange={ ( value ) => setPostMeta( { glb_sermon_passage: value } ) }
          help="eg: John 3:1–19"
      />
      <TextControl
          label='Sermon Date'
          value={ postMeta.glb_sermon_date}
          onChange={ ( value ) => setPostMeta( { glb_sermon_date: value } ) }
          type='date'
      />

      <MediaUploadCheck>
        <MediaUpload
          onSelect={ ( media ) => {
              setPostMeta( { glb_sermon_mp3: media.id.toString() } )
              audioUrl = media.url;
            }
          }
          allowedTypes={ 'audio' }
          value={ postMeta.glb_sermon_mp3 }
          render={ ( { open } ) => (
            <div>
              { audioUrl ?
                <div>
                  <p>Sermon Mp3</p>
                  <audio src={audioUrl} controls preload="metadata">Test</audio>
                  <Button isSecondary onClick={ open }>Replace mp3</Button>
                </div>
                :
                <div>
                  <p>Select the sermon to upload</p>
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
    const sermon = meta.glb_sermon_mp3;

    return {
      postMeta: meta,
      postType: select( 'core/editor' ).getCurrentPostType(),
      sermonUrl: select('core').getMedia(sermon),
    };
  } ),
  withDispatch( ( dispatch ) => {
    return {
      setPostMeta( newMeta ) {
        dispatch( 'core/editor' ).editPost( { meta: newMeta } );
      }
    };
  } )
] )(Fields);


