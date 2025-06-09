//app layout
import Layout from "../Layouts/Layout.vue";
//Dashboard
import Dashboard from "../Management/Dashboard/Dashboard.vue";
//SettingsRoutes
import SettingsRoutes from "../Management/Settings/setup/routes.js";
//UserRoutes
import UserRoutes from "../Management/UserManagement/User/setup/routes.js";
//routesimport BlogWriterRoutes from '../Management/BlogManagement/BlogWriter/setup/routes.js';
import BlogRoutes from '../Management/BlogManagement/Blog/setup/routes.js';
import BlogCategoryRoutes from '../Management/BlogManagement/BlogCategory/setup/routes.js';


const routes = {
  path: "",
  component: Layout,
  children: [
    {
      path: "dashboard",
      component: Dashboard,
      name: "adminDashboard",
    },
    //management routes        BlogWriterRoutes,
        BlogRoutes,
        BlogCategoryRoutes,





    //user routes
    UserRoutes,
    //settings
    SettingsRoutes,
  ],
};

export default routes;
