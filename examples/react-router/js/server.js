import React from 'react'
import { StaticRouter } from 'react-router-dom'
import MetaTagsServer from 'react-meta-tags/lib/meta_tags_server';
import { MetaTagsContext } from 'react-meta-tags/lib/index';

import App from './App'

var context = {}

export const metaTagsInstance = MetaTagsServer();

export default (props) => (
  <MetaTagsContext extract = {metaTagsInstance.extract}>
    <StaticRouter location={props.location} context={context}>
      <App/>
    </StaticRouter>
  </MetaTagsContext>
)
