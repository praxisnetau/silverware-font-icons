/* Webpack Configuration
===================================================================================================================== */

// Load Core Modules:

const path = require('path');
const webpack = require('webpack');

// Load Plugin Modules:

const ExtractTextPlugin = require('extract-text-webpack-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');

// Configure Paths:

const PATHS = {
  ADMIN: {
    SRC: path.resolve(__dirname, 'admin/client/src'),
    DIST: path.resolve(__dirname, 'admin/client/dist'),
    BUNDLES: path.resolve(__dirname, 'admin/client/src/bundles'),
    PUBLIC: '/silverware-font-icons/admin/client/dist/'
  },
  MODULES: path.resolve(__dirname, 'node_modules')
};

// Configure Style Loader:

const style = (env, loaders) => {
  return (env === 'production') ? ExtractTextPlugin.extract({
    fallback: 'style-loader',
    use: loaders
  }) : [{ loader: 'style-loader' }].concat(loaders);
};

// Configure Rules:

const rules = (env) => {
  return [
    {
      test: /\.js$/,
      use: [
        {
          loader: 'babel-loader'
        }
      ],
      exclude: [ PATHS.MODULES ]
    },
    {
      test: /\.css$/,
      use: style(env, [
        {
          loader: 'css-loader'
        },
        {
          loader: 'postcss-loader'
        }
      ])
    },
    {
      test: /\.scss$/,
      use: style(env, [
        {
          loader: 'css-loader'
        },
        {
          loader: 'postcss-loader'
        },
        {
          loader: 'sass-loader',
          options: {
            includePaths: [
              path.resolve(process.env.PWD, '../') // allows resolving of framework paths in symlinked modules
            ]
          }
        }
      ])
    },
    {
      test: /\.(gif|jpg|png)$/,
      use: [
        {
          loader: 'url-loader',
          options: {
            name: 'images/[name].[ext]',
            limit: 10000
          }
        }
      ]
    },
    {
      test: /\.svg$/,
      use: [
        {
          loader: 'file-loader',
          options: {
            name: 'svg/[name].[ext]'
          }
        },
        {
          loader: 'svgo-loader',
          options: {
            plugins: [
              { removeTitle: true },
              { convertColors: { shorthex: false } },
              { convertPathData: false }
            ]
          }
        }
      ]
    },
    {
      test: /\.(ttf|eot)(\?v=[0-9]\.[0-9]\.[0-9])?$/,
      use: [
        {
          loader: 'file-loader',
          options: {
            name: 'fonts/[name].[ext]'
          }
        }
      ]
    },
    {
      test: /\.woff(2)?(\?v=[0-9]\.[0-9]\.[0-9])?$/,
      use: [
        {
          loader: 'url-loader',
          options: {
            name: 'fonts/[name].[ext]',
            mimetype: 'application/font-woff',
            limit: 10000
          }
        }
      ]
    }
  ];
};

// Configure Devtool:

const devtool = (env) => {
  return (env === 'production') ? false : 'source-map';
};

// Configure Plugins:

const plugins = (env, src, dist) => {
  
  // Define Common Plugins:
  
  var common = [
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery'
    })
  ];
  
  // Answer Common + Environment-Specific Plugins:
  
  return common.concat((env === 'production') ? [
    new CleanWebpackPlugin([ dist ], {
      verbose: true
    }),
    new ExtractTextPlugin({
      filename: 'styles/[name].css',
      allChunks: true
    }),
    new webpack.optimize.UglifyJsPlugin({
      output: {
        beautify: false,
        comments: false,
        semicolons: false
      },
      compress: {
        unused: false,
        warnings: false
      }
    })
  ] : [
    
  ]);
  
};

// Define Configuration:

const config = (env) => {
  return [
    {
      entry: {
        'bundle': path.resolve(PATHS.ADMIN.BUNDLES, 'bundle.js')
      },
      output: {
        path: PATHS.ADMIN.DIST,
        filename: 'js/[name].js',
        publicPath: PATHS.ADMIN.PUBLIC
      },
      module: {
        rules: rules(env)
      },
      devtool: devtool(env),
      plugins: plugins(env, PATHS.ADMIN.SRC, PATHS.ADMIN.DIST),
      resolve: {
        alias: {
          'admin': path.resolve(process.env.PWD, '../silverstripe-admin/client/src'),
          'font-awesome$': path.resolve(PATHS.MODULES, 'font-awesome/scss/font-awesome.scss')
        },
        modules: [
          PATHS.ADMIN.SRC,
          PATHS.MODULES
        ]
      },
      externals: {
        jquery: 'jQuery',
        jQuery: 'jQuery'
      }
    }
  ];
};

// Define Module Exports:

module.exports = (env = {development: true}) => {
  process.env.NODE_ENV = (env.production ? 'production' : 'development');
  console.log(`Running in ${process.env.NODE_ENV} mode...`);
  return config(process.env.NODE_ENV);
};
