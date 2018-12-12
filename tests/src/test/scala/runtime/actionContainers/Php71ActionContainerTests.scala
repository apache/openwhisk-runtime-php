/*
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements.  See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

package runtime.actionContainers

import org.junit.runner.RunWith
import org.scalatest.junit.JUnitRunner
import actionContainers.ResourceHelpers.ZipBuilder
import spray.json._

@RunWith(classOf[JUnitRunner])
class Php71ActionContainerTests extends Php7ActionContainerTests {

  override lazy val phpContainerImageName = "action-php-v7.1"

  override val testLargeInput = TestConfig("", skipTest = true)

  it should "fail to initialize with bad code" in {
    val (out, err) = withPhp7Container { c =>
      val code = """
                |<?php
                | 10 PRINT "Hello world!"
                | 20 GOTO 10
            """.stripMargin

      val (initCode, error) = c.init(initPayload(code))
      initCode should not be (200)
      error shouldBe a[Some[_]]
      error.get shouldBe a[JsObject]
      error.get.fields("error").toString should include("PHP syntax error")
    }

    // Somewhere, the logs should mention an error occurred.
    checkStreams(out, err, {
      case (o, e) =>
        (o + e).toLowerCase should include("error")
        (o + e).toLowerCase should include("syntax")
    })
  }

  it should "fail gracefully on invalid zip files" in {
    // Some text-file encoded to base64.
    val code = "Q2VjaSBuJ2VzdCBwYXMgdW4gemlwLgo="

    val (out, err) = withPhp7Container { c =>
      val (initCode, error) = c.init(initPayload(code))
      initCode should not be (200)
      error shouldBe a[Some[_]]
      error.get shouldBe a[JsObject]
      error.get.fields("error").toString should include("Failed to open zip file")
    }

    // Somewhere, the logs should mention the failure
    checkStreams(out, err, {
      case (o, e) =>
        (o + e).toLowerCase should include("error")
        (o + e).toLowerCase should include("failed to open zip file")
    })
  }

  it should "fail gracefully on valid zip files that are not actions" in {
    val srcs = Seq(Seq("hello") -> """
                | Hello world!
            """.stripMargin)

    val code = ZipBuilder.mkBase64Zip(srcs)

    val (out, err) = withPhp7Container { c =>
      c.init(initPayload(code))._1 should not be (200)
    }

    checkStreams(out, err, {
      case (o, e) =>
        (o + e).toLowerCase should include("error")
        (o + e).toLowerCase should include("zipped actions must contain index.php at the root.")
    })
  }

  it should "fail gracefully on valid zip files with invalid code in index.php" in {
    val (out, err) = withPhp7Container { c =>
      val srcs = Seq(Seq("index.php") -> """
                    | <?php
                    | 10 PRINT "Hello world!"
                    | 20 GOTO 10
                """.stripMargin)

      val code = ZipBuilder.mkBase64Zip(srcs)

      val (initCode, error) = c.init(initPayload(code))
      initCode should not be (200)
      error shouldBe a[Some[_]]
      error.get shouldBe a[JsObject]
      error.get.fields("error").toString should include("PHP syntax error in index.php")
    }

    // Somewhere, the logs should mention an error occurred.
    checkStreams(out, err, {
      case (o, e) =>
        (o + e).toLowerCase should include("error")
        (o + e).toLowerCase should include("syntax")
    })
  }
}
