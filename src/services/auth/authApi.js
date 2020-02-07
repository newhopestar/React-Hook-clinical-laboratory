import { wrapRequest, xapi } from '../utils';

const login = wrapRequest(async (email, password) =>
  xapi().post('/user/login', {
      email: email,
      password: password
  })
);

const getUser = wrapRequest(async () => xapi().get('/api/user'));

export { login, getUser };
