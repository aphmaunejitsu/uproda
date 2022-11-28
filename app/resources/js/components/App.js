import React, { useEffect } from 'react';
import ReactDOM from 'react-dom';
import { loadReCaptcha } from 'react-recaptcha-v3';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';
// import useMediaQuery from '@material-ui/core/useMediaQuery';
import { createTheme, ThemeProvider } from '@material-ui/core/styles';
import useWindowDarkMode from './hook/useWindowDarkMode';

import HeaderBar from './HeaderBar';
import Top from './Top';
import About from './About';
import ImageDetail from './Detail';
import NotFoundPage from './NotFound';

function App() {
  const isDarkMode = useWindowDarkMode();
  const sitekey = process.env.MIX_RODA_GOOGLE_RECAPTCHA_SITEKEY;

  const theme = React.useMemo(() => createTheme({
    palette: { type: isDarkMode ? 'dark' : 'light' },
  }), [isDarkMode]);

  useEffect(() => {
    loadReCaptcha(sitekey);
  });

  return (
    <Router>
      <ThemeProvider theme={theme}>
        <HeaderBar />
        <Switch>
          <Route path="/" exact component={Top} />
          <Route path="/about" component={About} />
          <Route path="/image/:hash" component={ImageDetail} />
          <Route component={NotFoundPage} />
        </Switch>
      </ThemeProvider>
    </Router>
  );
}

if (document.getElementById('root')) {
  ReactDOM.render(
    <App />,
    document.getElementById('root'),
  );
}
