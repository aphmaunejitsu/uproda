import React, { useEffect } from 'react';
import { loadReCaptcha, ReCaptcha } from 'react-recaptcha-v3';

function ReCaptchaForm() {
  useEffect(() => {
    loadReCaptcha(process.env.MIX_RODA_GOOGLE_RECAPTCHA_SITEKEY);
  });
}

export default ReCaptchaForm;
