import React from 'react';
import ReactDOM from 'react-dom';
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
  // const prefersDarkMode = useMediaQuery('(prefers-color-scheme: dark)');
  const isDarkMode = useWindowDarkMode();

  const theme = React.useMemo(() => createTheme({
    palette: { type: isDarkMode ? 'dark' : 'light' },
  }), [isDarkMode]);

  return (
    <ThemeProvider theme={theme}>
      <Router>
        <>
          <HeaderBar />
          <Switch>
            <Route path="/" exact component={Top} />
            <Route path="/about" component={About} />
            <Route path="/image/:hash" component={ImageDetail} />
            <Route component={NotFoundPage} />
          </Switch>
        </>
      </Router>
    </ThemeProvider>
  );
}

if (document.getElementById('root')) {
  ReactDOM.render(<App />, document.getElementById('root'));
}
