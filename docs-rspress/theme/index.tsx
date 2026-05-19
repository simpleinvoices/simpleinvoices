import React from 'react';
import { HomeLayout as BasicHomeLayout, getCustomMDXComponent } from '@rspress/core/theme-original';
import { Content } from '@rspress/core/runtime';
import { MDXProvider } from '@mdx-js/react';
import './custom.css';

export function HomeLayout(props: any) {
  const mdxComponents = getCustomMDXComponent();
  return (
    <BasicHomeLayout
      {...props}
      afterHeroActions={
        <>
          {props.afterHeroActions}
          <MDXProvider components={mdxComponents}>
            <Content />
          </MDXProvider>
        </>
      }
    />
  );
}

export { default as EmbedDetector } from './EmbedDetector';

export * from '@rspress/core/theme-original';
