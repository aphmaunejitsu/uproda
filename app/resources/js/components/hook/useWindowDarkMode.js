import { useState, useEffect } from 'react';

function useWindowDarkMode() {
  const matchMediaPrefDark = window.matchMedia('(prefers-color-scheme: dark)');

  const isDarkMode = () => {
    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
      return true;
    }

    return false;
  };

  const [isWindowDarkMode, setWindowDarkMode] = useState(isDarkMode());

  useEffect(() => {
    const onModeChange = () => {
      setWindowDarkMode(isDarkMode());
    };
    matchMediaPrefDark.addEventListener('change', onModeChange);
  }, []);

  return isWindowDarkMode;
}

export default useWindowDarkMode;
