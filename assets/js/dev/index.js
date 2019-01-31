import {anticore} from 'anticore'
// Import your own middlewares here
import './sse-listener'
import './on-message'

// Let the following lines at the end of this file
import 'anticore/middleware/main/mono'
anticore.defaults().populate()
