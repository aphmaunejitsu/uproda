import React from 'react';
import { makeStyles } from '@material-ui/core/styles';
import DMMAd from './common/DMMAd';
import { Helmet } from 'react-helmet';

const useStyles = makeStyles({
  title: {
    width: '100%',
    'text-align': 'center',
    padding: '1rem',
  },
  kiyaku: {
    padding: '0.5rem',
    display: 'flex',
    justifyContent: 'center',
  },
  link: {
    color: 'blue'
  },
});

function About() {
  const classes = useStyles();
  return (
    <>
      <Helmet
        title={`${process.env.MIX_RODA_NAME} | About`}
      />
      <h1 className={classes.title}>About</h1>
      <div className={classes.kiyaku}>
        <dl>
          <dt>利用規約への同意・免責事項</dt>
          <dd>
            当サービスの利用者は本規約に同意したものとします。またご使用において如何なる損失が生じた場合でも当サイト責任者は責任を負わないものとします。
          </dd>
          <dt>禁止行為</dt>
          <dd>
            以下のファイルは予告なしに削除することがあります
            <ul>
              <li>法律や条令、公序良俗に反するファイル</li>
              <li>誹謗中傷するようなファイル</li>
              <li>第三者の権利を侵害するファイル</li>
              <li>その他、管理者が不適当と判断するファイル</li>
            </ul>
          </dd>
          <dt>免責事項</dt>
          <dd>
            当サイトで発生したいかなる不利益も一切の責任を負わないものとします。
          </dd>
          <dt>規約の改変</dt>
          <dd>
            本規約は予告することなく変更できるものとします。
          </dd>
          <dt>運営してる人</dt>
          <dd>
            <a className={classes.link}
              href={process.env.MIX_RODA_RENRAKU_TWITTER}
              target="_blank"
              rel="noopener noreferrer"
            >
              {process.env.MIX_RODA_RENRAKU_NAME}
            </a>
          </dd>
          <dt>作った人</dt>
          <dd>
            <a className={classes.link}
              href="https://twitter.com/shikyou1"
              target="_blank"
              rel="noopener noreferrer"
            >
              しきょういち
            </a>
          </dd>
        </dl>
      </div>
      <DMMAd dmmid={process.env.MIX_RODA_DMM_ID1} />
    </>
  );
}

export default About;
