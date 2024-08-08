import axios from 'axios'
import { useAuthStore } from '@/stores/auth.js'
// Request interceptor

axios.defaults.baseURL = '/api/';


axios.interceptors.request.use(request => {
  const auth = useAuthStore();
  if (auth.token) {
    request.headers.Authorization = `Bearer ${auth.token}`
  }else{
    request.headers.Authorization = ""
  }

  return request
});

axios.interceptors.response.use(function (response) {
    return response;
  }, function (err) {
        const error = {
            status: err.response?.status,
            original: err,
            validation: {},
            message: null,
        };
    switch (err.response?.status) {
      case 422:
        for (let field in err.response.data.result) {
          error.validation[field] = err.response.data.result[field][0];
        }
        break;
      case 403:
        error.message = "You're not allowed to do that.";
        break;
      case 401:
        // const auth = useAuthStore()
        // setTimeout(async () => await auth.logout(), 3000);
        error.message = "Please re-login.";
        break;
      case 500:
        error.message = "Something went really bad. Sorry.";
        break;
      default:
        error.message = "Something went wrong. Please try again later.";
    }
    return Promise.reject(error);
    // return error;
});
// axios.interceptors.response.use(response => {
// 	const { status } = response.request;
//     console.log(status);
// 	if (status === 401 || status === 419) {
// 		const auth = useAuthStore()
// 		setTimeout(async () => await auth.logout(), 3000)
// 		return Promise.reject({
// 			name: 'Permission denied',
// 			message: 'You lost your credentials - will be redirected to login page.',
// 		})
// 	}
// 	return response;
// });
// axios.interceptors.response.use(null, err => {
//     const error = {
//       status: err.response?.status,
//       original: err,
//       validation: {},
//       message: null,
//     };
   
//     switch (err.response?.status) {
//       case 422:
//         for (let field in err.response.data.errors) {
//           error.validation[field] = err.response.data.errors[field][0];
//         }
//         break;
//       case 403:
//         error.message = "You're not allowed to do that.";
//         break;
//       case 401:
//         error.message = "Please re-login.";
//         break;
//       case 500:
//         error.message = "Something went really bad. Sorry.";
//         break;
//       default:
//         error.message = "Something went wrong. Please try again later.";
//     }
   
//     return Promise.reject(error);
//   });